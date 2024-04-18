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
    this.someThingInner = apiDataBlock.querySelector(".some-class");

    // eslint-disable-next-line no-console
    console.log('apiDataBlock FrontendModule loaded');

    this.doSomething(this.someThingInner);

  }

  doSomething(someThingInner) {
    const self = this;

    // Using 'self' to access the class instance inside the event listener
    // eslint-disable-next-line no-console
    console.log(self.apiDataBlock);
    // eslint-disable-next-line no-console
    console.log('apiDataBlock FrontendModule Something happened' + someThingInner);
    // Do something
  }

}
