const bcrypt = require("bcryptjs");
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
    try {
      const userDetail = this.req.user;
      if (await bcrypt.compare(inputs.current_password, userDetail.password)) {
        let password = await bcrypt.hash(
          inputs.new_password,
          await bcrypt.genSalt(10)
        );
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
    } catch (error) {
      await general.errorLog(error, "user/change-password");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
