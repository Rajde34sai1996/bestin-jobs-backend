const twilio = require('twilio');
module.exports = {
  /**
   * 
   * @param {error} value  error
   * @param {file name} file  
   * @returns 
   */
  errorLog: async function (value, file, parameters = null) {
    return new Promise(async (resolve, reject) => {
      try {
        if (parameters) {
          parameters =
            typeof parameters === "string"
              ? parameters
              : JSON.stringify(parameters);
        }
        await Error_log.create({
          error_name: value.name ? value.name : "Error",
          error_name: value.name ? value.name : "Error",
          error_message: value.message ? value.message : "",
          error_path: value.stack ? value.stack : null,
          error_folder_path: file ? file : null,
          parameters: "Blank",
        }).fetch();
        resolve();
      } catch (error) {
        resolve();
      }
    });
  },

  generateRandom6DigitNumber: async function () {
    const min = 100000; // Smallest 6-digit number
    const max = 999999; // Largest 6-digit number
    return Math.floor(Math.random() * (max - min + 1)) + min;
  },

  /**
   * This function is used to send the otp
   * @param {to} to send the phone number
   * @returns { success: true}
   */
  sendOtp: async function (to) {
    try {
      const accountSid = sails.config.accountSid;
      const authToken = sails.config.authToken;
      const verifySid = sails.config.verifySid;
      const client = twilio(accountSid, authToken);
      const verification = await client.verify.v2
        .services(verifySid)
        .verifications.create({
          to,
          channel: "sms",
          ttl: 60, // Set the OTP expiration time to 60 seconds (1 minute)
        });

      return { success: true, data: verification };
    } catch (error) {
      this.errorLog(error, "services/sendOtp");
      return {
        succes: false,
        message: error.message,
      };
    }
  },

  /**
   * 
   * @param {phone_number} to 
   * @param {otp} code 
   * @returns 
   */
  checkOtp: async function (to, code) {
    try {
      const accountSid = sails.config.accountSid;
      const authToken = sails.config.authToken;
      const verifySid = sails.config.verifySid;
      const client = twilio(accountSid, authToken);

      const verificationCheck = await client.verify.v2
        .services(verifySid)
        .verificationChecks.create({ to, code });

      if (verificationCheck.status === "approved") {
        return {
          success: true,
          verificationCheck: verificationCheck,
        };
      } else {
        return {
          success: false,
          verificationCheck: verificationCheck,
        };
      }
    } catch (error) {
      this.errorLog(error, "services/checkOtp")
      return {
        succes: false,
        message: error.message,
      };
    }
  },
};
