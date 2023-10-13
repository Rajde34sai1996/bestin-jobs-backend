module.exports = {
  friendlyName: "Create",

  description: "Create otp.",

  inputs: {
    user_id: {
      type: "number",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      let findUser = await Otp.findOne({
        user_id: inputs.user_id,
      });
      let getOtp = await general.generateRandom6DigitNumber();
      if (findUser) {
        await Otp.update({ user_id: findUser.user_id }).set({ otp: getOtp });
      } else {
        await Otp.create({ user_id: inputs.user_id, otp: getOtp });
      }
      return exits.success({
        message: this.res.locals.__("Send OTP on your number."),
        success: true,
      });
    } catch (error) {
      await general.errorLog(error, "otp/create");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
