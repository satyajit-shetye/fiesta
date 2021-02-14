import { Injectable } from '@angular/core';
import { AuthenticationApiService } from './authentication-api.service';
import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { ProviderUserDetailsModel } from '../models';

@Injectable()
export class AuthenticationService extends AuthenticationApiService{

  constructor( _http : HttpClient) {
    super(_http);
  }

  //#region API's
  register<T>(user:any) : Observable<T> {
    return this.register(user);
  }

  login<T>(user:any) : Observable<T> {
    return this.login(user);
  }

  isEmailExists<T>(user:any) : Observable<T> {
    return this.isEmailExists(user);
  }

  changePassword<T>(user:any) : Observable<T> {
    return this.changePassword(user);
  }

  loginWithProvider<T>(user: ProviderUserDetailsModel) : Observable<T> {
    return this.loginWithProviderApi({
      email : user.email,
      first_name : user.firstName,
      last_name : user.lastName,
      provider : user.provider,
      provider_user_id : user.providerUserId
    });
  }

  //#endregion

  //#region Module Functions

  //#endregion
}
