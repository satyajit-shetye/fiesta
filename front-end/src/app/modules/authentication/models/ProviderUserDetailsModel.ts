export default class ProviderUserDetailsModels {
    constructor( public providerUserId : string,
        public email: string,
        public firstName: string,
        public lastName: string,
        public provider: string ){
    }
}