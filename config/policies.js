/**
 * Policy Mappings
 * (sails.config.policies)
 *
 * Policies are simple functions which run **before** your actions.
 *
 * For more information on configuring policies, check out:
 * https://sailsjs.com/docs/concepts/policies
 */

module.exports.policies = {
  /***************************************************************************
   *                                                                          *
   * Default policy for all controllers and actions, unless overridden.       *
   * (`true` allows public access)                                            *
   *                                                                          *
   ***************************************************************************/
  // "*": true,
  "email-template/create": "isAuthenticated",
  "email-template/index": "isAuthenticated",
  "email-template/update": "isAuthenticated",
  "email-template/delete": "isAuthenticated",

  "services/create": "isAuthenticated",
  "services/index": "isAuthenticated",
  "services/update": "isAuthenticated",
  "services/delete": "isAuthenticated",

  "api/getUserData": "isAuthenticated",
  "user/update-admin": "isAuthenticated",
  "user/change-password": "isAuthenticated",
  "user/update-avatar": "isAuthenticated",
  "user/create-admin": "isAuthenticated",
  "user/get-admin": "isAuthenticated",
  "user/delete": "isAuthenticated",
  "user/block-un-block": "isAuthenticated",
  "user/connect": "isAuthenticated",
};
