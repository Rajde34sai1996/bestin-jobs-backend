module.exports = {
  friendlyName: "Create",

  description: "Create otp.",

  inputs: {
    verify_by: {
      type: "string",
      required: true,
    },
    type: {
      type: "string",
      default: "join"
    }
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      if(inputs.type == "join") {
        let userDetails = await Users.findOne({
          phone_number: inputs.verify_by,
        });
        if (userDetails) {
          return exits.success({
            success: false,
            message: this.res.locals.__("You have a account please login"),
          });
        }
      }
      const { otp, timestamp } = await general.generateOTPWithTimestamp();
      let sendOtp = await general.sendOtp(inputs.verify_by, otp);
      if (!sendOtp.success) {
        return exits.success({
          success: false,
          message: "Something went wrong while creating otp.",
        });
      }
      let createOtp = await Otp.create({
        verify_by: inputs.verify_by,
        otp,
        expire_time: timestamp,
      }).fetch();
      if (createOtp) {
        return exits.success({
          success: true,
          message: "OTP sent and expire in 2 minit.",
          data: createOtp,
        });
      } else {
        return exits.success({
          success: false,
          message: "Something went wrong while creating otp.",
        });
      }
    } catch (error) {
      console.log("🚀 ~ file: create.js:35 ~ error:", error);
      // await general.errorLog(error, "otp/create");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
