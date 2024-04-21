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
      columns: columnsParam,
    };

    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

    // Set up the options for the fetch request, including headers
    const fetchOptions = {
      method: 'GET',
      headers: {
        'X-WP-Nonce': restNonce,
        'Content-Type': 'application/json',
      },
    };

    return fetch(url, fetchOptions)
      .then(response => response.json())
      .then(response => {
        if (response.code === 'success' && response.data) {
          return response.data;
        }
        if (response.message) {
          return response.message;
        }
        // eslint-disable-next-line no-console
        console.error('Error fetching API data');
      })
      .catch(error => {
        // eslint-disable-next-line no-console
        console.error('Error:', error);
      });
  }

}
