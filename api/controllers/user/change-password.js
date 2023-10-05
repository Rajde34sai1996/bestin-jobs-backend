var bcrypt = require("bcryptjs");
module.exports = {
  friendlyName: "Reset",

  description: "Reset password",

  inputs: {
    current_password: {
      type: "string",
      required: true,
    },
    new_password: {
      type: "string",
      required: true,
    },
  },

  exits: {
    invalid: {
      statusCode: 409,
      description: "",
    },
  },
  fn: async function (inputs, exits) {
    const userDetail = this.req.user;
    if (await bcrypt.compare(inputs.current_password, userDetail.password)) {
      var salt = await bcrypt.genSalt(10);
      var password = await bcrypt.hash(inputs.new_password, salt);
      await Users.updateOne({
        id: userDetail.id,
      }).set({ password });
      return exits.success({
        success: true,
        message: this.res.locals.__("Password has been updated successfully"),
      });
    } else {
      return exits.success({
        success: false,
        message: this.res.locals.__("Current password is wrong!"),
      });
    }
  },
};
