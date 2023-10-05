/**
 * mailer.js
 *
 */
const webUrl = sails.config.webUrl;
module.exports = {
  sendMail: async function (data) {
    return await new Promise((resolve, reject) => {
      let obj = {
        from: "QURP",
        to: data.to,
        subject: data.subject,
        html: data.html,
      };
      sails.hooks.email.send(
        "html",
        {
          html: data.html,
        },
        obj,
        async function (err) {
          if (err) {
            console.log(err);
            reject(false);
          } else {
            sails.log.info("Mail Sent Successfully.");
            resolve(true);
          }
        }
      );
    });
  },
  replaceAll: function (str, map) {
    for (key in map) {
      str = str.replace(key, map[key]);
    }
    return str;
  },
  getMailHtml: async function (slug, array) {
    var html = "";
    let records = await Email_template.findOne({ slug });
    var str = records.content;
    var map = array;
    html = this.replaceAll(str, map);
    return {
      subject: records.subject,
      html,
    };
  },
  sendSetPasswordMail: async function (email, createObj) {
    const response = await this.getMailHtml("set-password-admin", {
      "{{name}}": createObj.name,
      "{{link}}": `${webUrl}/authentication/${createObj.token}`,
    });
    await this.sendMail({
      to: email,
      subject: response.subject,
      html: response.html,
    });
    return true;
  },
  sendVerificationCode: async function (email, code) {
    const response = await this.getMailHtml("verification-code", {
      "{{code}}": code,
    });
    await this.sendMail({
      to: email,
      subject: response.subject,
      html: response.html,
    });
    return true;
  },
};
