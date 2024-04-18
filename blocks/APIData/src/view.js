/**
 * Block frontend view scripts
 */
import FrontendModule from "./Handlers/FrontendModule";

(function () {
  "use strict";

  /**
   * Init block on load
   */
  window.addEventListener('load', () => {
    blockInit();
  });

  /**
   * Init block
   */
  function blockInit() {

    const apiDataBlocks = document.getElementsByClassName('api-data-block');

    if (apiDataBlocks.length > 0) {
      for (let apiDataBlock of apiDataBlocks) {
        new FrontendModule(apiDataBlock);
      }
    }
  }

})();
