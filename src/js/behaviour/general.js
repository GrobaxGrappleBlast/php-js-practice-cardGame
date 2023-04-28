export class generalMethods {

    static async sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    static sleepSync(ms) {
        const start = Date.now();
        while (Date.now() - start < ms) {}
      }

}