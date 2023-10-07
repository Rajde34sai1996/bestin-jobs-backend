module.exports = {
  friendlyName: "Create",

  description: "Create cms.",

  inputs: {
    slug: {
      type: "string",
      required: true,
    },
    title: {
      type: "string",
      required: true,
    },
    content: {
      type: "string",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      let cmsCreate = await Cms.create({
        title: inputs.title,
        slug: inputs.slug,
        content: inputs.content,
      }).fetch();
      return exits.success({
        success: true,
        data: cmsCreate,
      });
    } catch (error) {
      await general.errorLog(error, "cms/create")
      return exits.success({
        success: false,
        message: "Somethinng want wrong!"
      })
    }
  },
};
