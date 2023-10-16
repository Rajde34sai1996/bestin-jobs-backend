module.exports = {
  friendlyName: "Delete",

  description: "Delete cms.",

  inputs: {
    id: {
      type: "number",
      required: true,
      description: "The ID of the CMS item to delete.",
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      // Find the CMS item by ID
      const cmsItem = await Cms.findOne({ id: inputs.id });

      // Check if the CMS item exists
      if (!cmsItem) {
        return exits.success({ success: false, message: "CMS item not found" });
      }

      // Delete the CMS item
      await Cms.destroyOne({ id: inputs.id });

      return exits.success({ success: false, message: "CMS item deleted successfully" });
    } catch (error) {
      await general.errorLog(error, "cms/delete");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
