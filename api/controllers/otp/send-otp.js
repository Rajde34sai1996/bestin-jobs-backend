module.exports = {
  friendlyName: "Send otp",

  description: "This is used for send the otp on phone number",

  inputs: {
    to: {
      type: "string",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      let getOtpData = await general.sendOtp(inputs.to);
      if(getOtpData.success) {
        return exits.success({
          success: true,
          message: "OTP send your phone number."
        })
      } else {
        return exits.success({
          success: false,
          message: "Your phone number needs to be entered once again."
        })
      }
    } catch (error) {
      await general.errorLog(error, "otp/send-otp");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
