/**
 * Error_log.js
 *
 * @description :: A model definition represents a database table/collection.
 * @docs        :: https://sailsjs.com/docs/concepts/models-and-orm/models
 */

module.exports = {
  attributes: {
    error_name: { type: "string" },
    error_message: { type: "string" },
    error_path: { type: "string" },
    error_folder_path: { type: "string" },
    parameters: { type: "string" },
  },
};

