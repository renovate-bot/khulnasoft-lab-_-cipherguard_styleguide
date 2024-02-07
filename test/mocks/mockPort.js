/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) 2020 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         3.0.0
 */

import MockPort from "../../src/react-extension/test/mock/MockPort";
import mockRequestFoldersCreate from "./request/mockRequestFoldersCreate";
import mockRequestFoldersDelete from "./request/mockRequestFoldersDelete";
import mockRequestFoldersUpdate from "./request/mockRequestFoldersUpdate";
import mockRequestFoldersFindPermissions from "./request/mockRequestFoldersFindPermissions";
import mockRequestFoldersUpdateLocalStorage from "./request/mockRequestFoldersUpdateLocalStorage";
import mockRequestResourcesCreate from "./request/mockRequestResourcesCreate";
import mockRequestResourcesUpdate from "./request/mockRequestResourcesUpdate";
import mockRequestResourcesUpdateLocalStorage from "./request/mockRequestResourcesUpdateLocalStorage";
import mockRequestSecretDecrypt from "./request/mockRequestSecretEditDecrypt";
import mockRequestShareGetResources from "./request/mockRequestShareGetResources";
import mockRequestShareSearchAros from "./request/mockRequestShareSearchAros";
import mockRequestSiteSettings from "./request/mockRequestSiteSettings";
import mockRequestUserGet from "./request/mockRequestUserGet";
import mockRequestTagsUpdateResourceTags from "./request/mockRequestTagsUpdateResourceTags";
import mockRequestCommentsFind from "./request/mockRequestCommentsFind";
import mockRequestCommentsCreate from "./request/mockRequestCommentsCreate";
import mockRequestResourceUpdateDescription from "./request/mockRequestResourceUpdateDescription";
import mockRequestTagsGet from "./request/mockRequestTagsGet";
import mockRequestUpdateTags from "./request/mockRequestTagsUpdate";
import mockRequestDeleteTags from "./request/mockRequestDeleteTags";
import mockRequestResourcesFindPermissions from "./request/mockRequestResourcesFindPermissions";
import mockRequestResourceAddFavorite from "./request/mockRequestResourceAddFavorite";
import mockRequestResourceDeleteFavorite from "./request/mockRequestResourceDeleteFavorite";
import mockRequestResourcesDelete from "./request/mockRequestResourcesDelete";
import mockRequestGetVersion from "./request/mockRequestGetVersion";
import mockRequestUsersUpdateLocalStorage from "./request/mockRequestUsersUpdateLocalStorage";
import mockRequestGroupsUpdateLocalStorage from "./request/mockRequestGroupsUpdateLocalStorage";
import mockRequestResources from "./request/mockRequestResources";
import mockRequestUsersFindLoggedInUser from "./request/mockRequestUsersFindLoggedInUser";
import mockRequestGpgKeysFindByUserId from "./request/mockRequestGpgKeysFindByUserId";
import mockRequestPrivateKeys from "./request/mockRequestPrivateKey";
import mockRequestUserDeleteDryRun from "./request/mockRequestUserDeleteDryRun";
import mockRequestImportFile from "./request/mockRequestImportFile";
import mockRequestDisableMFA from "./request/mockRequestDisableMFA";
import mockRequestGroupDeleteDryRun from "./request/mockRequestGroupDeleteDryRun";
import mockRequestGroupsCreate from "./request/mockRequestGroupsCreate";
import mockRequestGroupsUpdate from "./request/mockRequestGroupsUpdate";
import mockRequestFindAllThemes from "./request/mockRequestFindAllThemes";
import mockRequestFindActivities from "./request/mockRequestFindActivities";
import mockRequestAuthIsAuthenticated from "./request/mockRequestAuthIsAuthenticated";
import mockRequestGetLocale from "./request/mockRequestGetLocale";
import mockRequestRoleGet from "./request/mockRequestRoleGet";
import mockRequestPasswordPolicies from "./request/mockRequestPasswordPolicies";
import mockRequestMobileTransferCreate from "./request/mockRequestMobileTransferCreate";
import mockRequestMobileTransferGet from "./request/mockRequestMobileTransferGet";
import mockRequestMobileTransferUpdate from "./request/mockRequestMobileTransferUpdate";
import mockRequestAccountRecoveryGetAccount from "./request/mockRequestAccountRecoveryGetAccount";
import mockRequestHasUserEnabledAccountRecovery from "./request/mockRequestHasUserEnabledAccountRecovery";
import mockRequestRbacsFindMe from "./request/mockRequestRbacsFindMe";

export default storage => {
  const mockPort = new MockPort(storage);
  mockPort.addRequestListener("cipherguard.folders.create", mockRequestFoldersCreate);
  mockPort.addRequestListener("cipherguard.folders.delete", mockRequestFoldersDelete);
  mockPort.addRequestListener("cipherguard.folders.update", mockRequestFoldersUpdate);
  mockPort.addRequestListener("cipherguard.folders.find-permissions", mockRequestFoldersFindPermissions);
  mockPort.addRequestListener("cipherguard.user.get", mockRequestUserGet);
  mockPort.addRequestListener("cipherguard.role.get-all", mockRequestRoleGet);
  mockPort.addRequestListener("cipherguard.organization-settings.get", mockRequestSiteSettings);
  mockPort.addRequestListener("cipherguard.recover.site-settings", mockRequestSiteSettings);
  mockPort.addRequestListener("cipherguard.setup.site-settings", mockRequestSiteSettings);
  mockPort.addRequestListener("cipherguard.folders.update-local-storage", mockRequestFoldersUpdateLocalStorage);
  mockPort.addRequestListener("cipherguard.resources.update-local-storage", mockRequestResourcesUpdateLocalStorage);
  mockPort.addRequestListener("cipherguard.users.update-local-storage", mockRequestUsersUpdateLocalStorage);
  mockPort.addRequestListener("cipherguard.users.find-logged-in-user", mockRequestUsersFindLoggedInUser);
  mockPort.addRequestListener("cipherguard.resources.create", mockRequestResourcesCreate);
  mockPort.addRequestListener("cipherguard.resources.update", mockRequestResourcesUpdate);
  mockPort.addRequestListener("cipherguard.share.get-resources", mockRequestShareGetResources);
  mockPort.addRequestListener("cipherguard.share.search-aros", mockRequestShareSearchAros);
  mockPort.addRequestListener("cipherguard.secret.decrypt", mockRequestSecretDecrypt);
  mockPort.addRequestListener("cipherguard.khulnasoft.comments.create", mockRequestCommentsCreate);
  mockPort.addRequestListener("cipherguard.khulnasoft.comments.find-all-by-resource", mockRequestCommentsFind);
  mockPort.addRequestListener("cipherguard.resource.update-description", mockRequestResourceUpdateDescription);
  mockPort.addRequestListener("cipherguard.tags.find-all", mockRequestTagsGet);
  mockPort.addRequestListener("cipherguard.tags.update", mockRequestUpdateTags);
  mockPort.addRequestListener("cipherguard.tags.update-resource-tags", mockRequestTagsUpdateResourceTags);
  mockPort.addRequestListener("cipherguard.tags.delete", mockRequestDeleteTags);
  mockPort.addRequestListener("cipherguard.resources.find-permissions", mockRequestResourcesFindPermissions);
  mockPort.addRequestListener("cipherguard.favorite.add", mockRequestResourceAddFavorite);
  mockPort.addRequestListener("cipherguard.favorite.delete", mockRequestResourceDeleteFavorite);
  mockPort.addRequestListener("cipherguard.resources.delete-all", mockRequestResourcesDelete);
  mockPort.addRequestListener("cipherguard.actionlogs.find-all-for", mockRequestFindActivities);
  mockPort.addRequestListener("cipherguard.addon.get-version", mockRequestGetVersion);
  mockPort.addRequestListener("cipherguard.groups.update-local-storage", mockRequestGroupsUpdateLocalStorage);
  mockPort.addRequestListener("cipherguard.resources.find-all", mockRequestResources);
  mockPort.addRequestListener("cipherguard.keyring.get-public-key-info-by-user", mockRequestGpgKeysFindByUserId);
  mockPort.addRequestListener("cipherguard.keyring.get-private-key", mockRequestPrivateKeys);
  mockPort.addRequestListener("cipherguard.users.delete-dry-run", mockRequestUserDeleteDryRun);
  mockPort.addRequestListener("cipherguard.import-passwords.import-file", mockRequestImportFile);
  mockPort.addRequestListener("cipherguard.users.disable-mfa", mockRequestDisableMFA);
  mockPort.addRequestListener("cipherguard.groups.delete-dry-run", mockRequestGroupDeleteDryRun);
  mockPort.addRequestListener("cipherguard.groups.create", mockRequestGroupsCreate);
  mockPort.addRequestListener("cipherguard.groups.update", mockRequestGroupsUpdate);
  mockPort.addRequestListener("cipherguard.themes.find-all", mockRequestFindAllThemes);
  mockPort.addRequestListener("cipherguard.auth.is-authenticated", mockRequestAuthIsAuthenticated);
  mockPort.addRequestListener("cipherguard.locale.get", mockRequestGetLocale);
  mockPort.addRequestListener("cipherguard.password-policies.get", mockRequestPasswordPolicies);
  mockPort.addRequestListener("cipherguard.mobile.transfer.create", mockRequestMobileTransferCreate);
  mockPort.addRequestListener("cipherguard.mobile.transfer.update", mockRequestMobileTransferUpdate);
  mockPort.addRequestListener("cipherguard.mobile.transfer.get", mockRequestMobileTransferGet);
  mockPort.addRequestListener("cipherguard.account-recovery.get-account", mockRequestAccountRecoveryGetAccount);
  mockPort.addRequestListener("cipherguard.recover.has-user-enabled-account-recovery", mockRequestHasUserEnabledAccountRecovery);
  mockPort.addRequestListener("cipherguard.rbacs.find-me", mockRequestRbacsFindMe);

  // Deprecated events
  const deprecatedEvent = () => { throw new Error(`This event is deprecated.`); };
  mockPort.addRequestListener("cipherguard.site.settings", deprecatedEvent);
  mockPort.addRequestListener("cipherguard.recover.site-settings", deprecatedEvent);
  mockPort.addRequestListener("cipherguard.setup.site-settings", deprecatedEvent);

  return mockPort;
};

