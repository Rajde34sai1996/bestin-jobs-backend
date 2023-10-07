module.exports = {


  friendlyName: 'List',


  description: 'List cms.',


  inputs: {

  },


  exits: {

  },


  fn: async function (inputs, exits) {

    try {
      const cmsItems = await Cms.find();

      return exits.success({success: true, data: cmsItems });
    } catch (error) {
      await general.errorLog(error, "cms/list")
      return exits.success({
        success: false,
        message: "Somethinng want wrong!"
      })
    }

  }


};
