const jwt = require("jsonwebtoken");
module.exports = {
  friendlyName: "Verify otp",

  description: "Verify the otp",

  inputs: {
    otpId: {
      type: "number",
      required: true,
    },
    otp: {
      type: "number",
      required: true,
    },
    phone_number: {
      type: "string",
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      let findOtpRecord = await Otp.findOne({ id: inputs.otpId });
      if (!findOtpRecord) {
        return exits.success({ success: false, message: "OTP Invalid" });
      }
      if (findOtpRecord.expire_time > Date.now()) {
        // OTP is valid; you can proceed
        let userDetails = null;
        let token = null;

        if (inputs.phone_number) {
          userDetails = await Users.findOne({
            phone_number: inputs.phone_number,
          });

          if (userDetails) {
            // Only generate a token if the user exists
            token = jwt.sign(
              {
                user: userDetails,
              },
              sails.config.jwtSecret,
              {
                expiresIn: sails.config.jwtExpires,
              }
            );
          }
        }

        await Otp.destroy({ id: inputs.otpId });

        return exits.success({
          success: true,
          message: "OTP verified",
          data: {
            token: token,
            user: userDetails,
          },
        });
      } else {
        await Otp.destroy({ id: findOtpRecord.id });
        return exits.success({ success: false, message: "OTP expired" });
      }
    } catch (error) {
      await general.errorLog(error, "otp/verify-otp");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
