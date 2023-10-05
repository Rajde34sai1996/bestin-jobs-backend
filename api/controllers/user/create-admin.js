module.exports = {
  friendlyName: "Create",

  description: "Create Admin.",

  inputs: {
    name: {
      type: "string",
      required: true,
    },
    email: {
      type: "string",
      required: true,
    },
    avatar: {
      type: "string",
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
    const userDetail = this.req.user;
    if (userDetail.user_type === "admin") {
      var email = inputs.email.trim();
      var checkAdmin = await Users.count({
        email,
      });
      if (checkAdmin) {
        return exits.success({
          message: this.res.locals.__("Admin already exists."),
          success: false,
        });
      }
      var createObj = {
        name: inputs.name,
        email,
        user_type: "admin",
        token: Math.random().toString(36),
      };
      if (inputs.avatar) {
        var uploadImage = await user.uploadAvatar(inputs.avatar, "avatar");
        createObj.avatar = uploadImage;
      }
      var createAdmin = await Users.create(createObj).fetch();
      mailer.sendSetPasswordMail(email, createObj);
      if (createAdmin) {
        return exits.success({
          message: this.res.locals.__("Admin has been added sucessfully."),
          data: createAdmin,
          success: true,
        });
      }
    } else {
      return exits.success({
        message: this.res.locals.__("Invalid User"),
        success: false,
      });
    }
  },
};
