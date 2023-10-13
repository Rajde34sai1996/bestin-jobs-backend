module.exports = {
  friendlyName: "Check otp",

  description: "This api is used to check the otp.",

  inputs: {
    to: {
      type: "string",
      required: true
    },
    otp: {
      type: "string",
      required: true
    }
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      let checkOtpData = await general.checkOtp(inputs.to, inputs.otp);
      console.log("🚀 ~ file: check-otp.js:22 ~ checkOtpData:", checkOtpData)
      if(checkOtpData.success) {
        return exits.success({ success: true, message: "OTP verified" });
      } else {
        return exits.success({
          success: false,
          message: "OTP verification failed",
        });
      }
    } catch (error) {
      console.log("🚀 ~ file: check-otp.js:31 ~ error:", error)
      // await general.errorLog(error, "otp/check-otp");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
