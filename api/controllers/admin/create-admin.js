const bcrypt = require("bcryptjs");
const random = require("random-key");
module.exports = {
  friendlyName: "Create",

  description: "Create Admin.",

  inputs: {
    name: {
      type: "string",
      required: true,
    },
    email: {
      required: true,
      unique: true,
      type: "string",
      isEmail: true,
      description: "The email address for the new account, e.g. m@example.com.",
      extendedDescription: "Must be a valid email address.",
    },
    password: {
      required: true,
      type: "string",
    },
    phone_no: {
      type: "number",
      required: true,
    },
    phone_code: {
      type: "string",
      required: true,
    },
  },
  exits: {
    invalid: {
      statusCode: 409,
      description: "firstname, lastname, email and password is required.",
    },
    redirect: {
      responseType: "redirect",
    },
  },

  fn: async function (inputs, exits) {
    try {
      var email = inputs.email.trim();
      var checkUser = await Users.count({
        email,
      });
      if (checkUser) {
        return exits.success({
          message: this.res.locals.__("User already exists."),
          success: false,
        });
      }

      var password = await bcrypt.hash(
        inputs.password,
        await bcrypt.genSalt(10)
      );
      var createUser = await Users.create({
        name: inputs.name.trim(),
        email,
        password,
        user_type: "admin",
        token: random.generateDigits(4),
        phone_no: inputs.phone_no,
        phone_code: inputs.phone_code,
      }).fetch();
      if (createUser) {
        // mailer.sendVerificationCode(email, createUser.token);
        return exits.success({
          message: this.res.locals.__("Account has been created successfully."),
          success: true,
          data: createUser.id,
        });
      } else {
        return exits.success({
          success: false,
        });
      }
    } catch (error) {
      await general.errorLog(error, "admin/create-admin");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
