import { Injectable } from '@angular/core';
import { AuthenticationApiService } from './authentication-api.service';
import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';

@Injectable()
export class AuthenticationService extends AuthenticationApiService{

  constructor( _http : HttpClient) {
    super(_http);
  }

  //#region API's
  register<T>(user:any) : Observable<T> {
    return this.registerApi(user);
  }

  login<T>(user:any) : Observable<T> {
    return this.loginApi(user);
  }

  isEmailExists<T>(user:any) : Observable<T> {
    return this.isEmailExistsApi(user);
  }

  changePassword<T>(user:any) : Observable<T> {
    return this.changePassword(user);
  }

  //#endregion

  //#region Module Functions

  //#endregion
}
