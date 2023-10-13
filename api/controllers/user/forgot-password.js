const bcrypt = require("bcryptjs");
module.exports = {
  friendlyName: "Forgot password",

  description: "",

  inputs: {
    email: {
      type: "string",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      let findUser = await Users.findOne({ email: inputs.email });
      if (!findUser) {
        return exits.success({ success: false, message: "User not found!" });
      }
      let password = Math.random().toString(36).slice(-8);
      let hashPassword = await bcrypt.hash(password, await bcrypt.genSalt(10));
      console.log("🚀 ~ file: forgot-password.js:22 ~ password:", password);
      await Users.updateOne({ id: findUser.id }).set({ password: hashPassword });
      return exits.success({ success: true, message: "Password upadted." });
    } catch (error) {
      await general.errorLog(error, "user/forgot-password");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
