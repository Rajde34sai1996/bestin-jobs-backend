module.exports = {
  friendlyName: "Update",

  description: "Update cms.",

  inputs: {
    slug: {
      type: "string",
    },
    title: {
      type: "string",
    },
    content: {
      type: "string",
    },
    id: {
      type: "number",
      required: true,
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

      // Update the CMS item with the provided data
      const updatedCmsItem = await Cms.updateOne({ id: inputs.id }).set({
        slug: inputs.slug || cmsItem.slug,
        title: inputs.title || cmsItem.title,
        content: inputs.content || cmsItem.content,
      });

      if (!updatedCmsItem) {
        return exits.success({
          success: false,
          message: "Invalid input data or update failed",
        });
      }

      return exits.success({
        message: "CMS item updated successfully",
        updatedCmsItem,
      });
    } catch (error) {
      await general.errorLog(error, "cms/update");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
