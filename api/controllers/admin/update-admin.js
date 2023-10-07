module.exports = {
  friendlyName: "Update",

  description: "Update user.",

  inputs: {
    name: {
      type: "string",
      required: true,
    },
    id: {
      type: "string",
      required: true,
    },
    avatar: {
      type: "string",
    },
    old_avatar: {
      type: "string",
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    const avatar = inputs.avatar;
    var setObj = {
      name: inputs.name,
    };
    if (_.isString(avatar) && avatar) {
      if (avatar.indexOf("http://") == 0 || avatar.indexOf("https://") == 0) {
        uploadImage = avatar;
      } else {
        var uploadImage = await user.uploadAvatar(avatar, "avatar");
        setObj.avatar = uploadImage;
        if (inputs.old_avatar) user.removeImage(inputs.old_avatar);
      }
    }
    var updateAdmin = await Users.updateOne({ id: inputs.id }).set(setObj);
    return exits.success({
      message: this.res.locals.__("Profile has been updated successfully."),
      data: updateAdmin,
      success: true,
    });
  },
};
