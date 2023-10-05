const user = require("../../services/user");

module.exports = {
  friendlyName: "Update",

  description: "Update avatar.",

  inputs: {
    avatar: {
      type: "string",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    const avatar = inputs.avatar;
    if (!_.isEmpty(inputs.avatar)) {
      if (avatar.indexOf("http://") == 0 || avatar.indexOf("https://") == 0) {
        uploadImage = avatar;
      } else {
        var uploadImage = await user.uploadAvatar(avatar, "avatar");
        user.removeImage(this.req.user.avatar);
      }
    }
    var updatedUser = await Users.updateOne({ id: this.req.user.id }).set({
      avatar: uploadImage,
    });
    return exits.success({
      message: this.res.locals.__("Avatar has been updated successfully."),
      data: updatedUser,
      success: true,
    });
  },
};
