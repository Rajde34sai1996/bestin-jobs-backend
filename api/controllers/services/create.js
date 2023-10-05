module.exports = {
  friendlyName: "Create",

  description: "Create Services.",

  inputs: {
    name: {
      type: "string",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    const userDetail = this.req.user;
    if (userDetail.user_type === "admin") {
      var name = inputs.name.trim();
      var checkService = await Services.count({
        name,
      });
      if (checkService) {
        return exits.success({
          message: this.res.locals.__("Service already exists."),
          success: false,
        });
      }
      var createService = await Services.create({
        name: inputs.name,
      }).fetch();
      return exits.success({
        message: this.res.locals.__("Service has been added successfully."),
        success: true,
        data: createService,
      });
    } else {
      return exits.success({
        message: this.res.locals.__("Invalid User"),
        success: false,
      });
    }
  },
};
