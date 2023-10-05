var fs = require("fs");
var AWS = require("aws-sdk");
AWS.config.update({
  accessKeyId: sails.config.spaceKey,
  secretAccessKey: sails.config.spaceSecret,
});
module.exports = {
  uploadAvatar: async function (avatar, type) {
    return new Promise((resolve, reject) => {
      if (_.isString(avatar) && !_.isEmpty(avatar)) {
        var base64String = avatar;
        var base64Image = base64String.split(";base64,").pop();
        var extension = undefined;
        var lowerCase = base64String.split(";base64,")[0];
        if (lowerCase.indexOf("png") !== -1) {
          extension = "png";
        } else if (
          lowerCase.indexOf("jpg") !== -1 ||
          lowerCase.indexOf("jpeg") !== -1
        ) {
          extension = "jpg";
        } else {
          reject("Invalid type");
        }
        var imagName = Date.now() + "." + extension;
        var _tmp = sails.config.appPath + "/assets/images/temp/" + imagName;
        fs.writeFile(_tmp, base64Image, { encoding: "base64" }, function (err) {
          if (err) throw err;
          const fileContent = fs.readFileSync(_tmp);
          var s3 = new AWS.S3({
            endpoint: sails.config.endPoint + "/" + type + "/",
          });
          var params = {
            Bucket: sails.config.bucket,
            Key: imagName,
            Body: fileContent,
            ACL: "public-read",
          };
          s3.putObject(params, function (err, data) {
            if (err) {
              reject(err);
            } else {
              fs.unlink(`${_tmp}`, (err) => {});
              resolve(sails.config.fullUrl + "/" + type + "/" + imagName);
            }
          });
        });
      } else {
        reject("No image found");
      }
    });
  },

  removeImage: async function (url) {
    return new Promise((resolve, reject) => {
      if (!_.isEmpty(url)) {
        var s3 = new AWS.S3({
          endpoint: sails.config.endPoint,
          accessKeyId: sails.config.spaceKey,
          secretAccessKey: sails.config.spaceSecret,
        });
        var str = url.split("/");
        var params = {
          Bucket: sails.config.bucket,
          Key: str[3] + "/" + str[4],
        };

        s3.deleteObject(params, function (err, data) {
          if (err) {
            reject(err.stack);
          } else {
            resolve(true);
          }
        });
      }
    });
  },

  removeData: async function (data) {
    if (data.avatar) this.removeImage(data.avatar);
    return true;
  },

  slugify: function (string) {
    return string
      .toString()
      .trim()
      .toLowerCase()
      .replace(/\s+/g, "-")
      .replace(/[^\w\-]+/g, "")
      .replace(/\-\-+/g, "-")
      .replace(/^-+/, "")
      .replace(/-+$/, "");
  },
};
