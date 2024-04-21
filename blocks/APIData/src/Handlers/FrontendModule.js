/* global APIBasedPluginFrontendData */
const {restApiUrl, restNamespace, restNonce} = APIBasedPluginFrontendData;

/**
 * Block front class
 */
export default class FrontendModule {

  /**
   * Constructor
   *
   * @param {Element} apiDataBlock
   */
  constructor(apiDataBlock) {
    this.apiDataBlock = apiDataBlock;

    const columnsData = this.apiDataBlock.getAttribute('data-columns');

    this.apiDataBlock.classList.add('spinner');

    this.fetchAPIData(columnsData).then(data => {
      this.apiDataBlock.classList.remove('spinner');
      this.apiDataBlock.innerHTML = data;

      /**
       *  Create and dispatch the apiDataBlockFetchComplete event
       *  Need to re-init blocks after apiDataBlock section fetch complete
       */
      const apiDataBlockFetchComplete = new CustomEvent('apiDataBlockFetchComplete',
        {
          detail: {
            responseData: data,
            columns: columnsData,
          },
        });
      document.dispatchEvent(apiDataBlockFetchComplete);

    });

  }

  fetchAPIData(columnsParam) {
    const url = new URL(restApiUrl + restNamespace + '/api-data');

    const params = {
      columns: encodeURIComponent(columnsParam),
      nonce: restNonce,
    };

    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

    return fetch(url, {method: 'GET'})
      .then(response => response.json())
      .then(response => {
        return response.data;
      })
      .catch(error => {
        // eslint-disable-next-line no-console
        console.error('Error:', error);
      });
  }

}
