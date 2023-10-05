var bcrypt = require("bcryptjs");
module.exports = {
  friendlyName: "Set",

  description: "Set Password",

  inputs: {
    new_password: {
      type: "string",
      required: true,
    },
    token: {
      type: "string",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    var checkLink = await Users.findOne({
      token: inputs.token,
    });
    if (checkLink) {
      await Users.updateOne({ id: checkLink.id }).set({
        is_email_verified: 1,
        token: null,
        password: await bcrypt.hash(
          inputs.new_password,
          await bcrypt.genSalt(10)
        ),
      });
      return exits.success({
        message: this.res.locals.__("Password has been set successfully."),
        success: true,
      });
    } else {
      return exits.success({
        message: this.res.locals.__("Can not set password!"),
        success: false,
      });
    }
  },
};
