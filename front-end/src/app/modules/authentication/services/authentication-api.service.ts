import { BaseApiService } from '../../shared/services/base-api.service';
import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';

export class AuthenticationApiService extends BaseApiService{

  private apiUrl : string;

  constructor( _http : HttpClient) {
    super(_http);
    this.apiUrl = this.baseApiUrl + '/authentication';
  }

  protected registerApi<T>(user:any) : Observable<T> {
    return this.post<T>(this.apiUrl + '/register', user);
  }

  protected loginApi<T>(user:any) : Observable<T> {
    return this.post<T>(this.apiUrl + '/login', user);
  }

  protected isEmailExistsApi<T>(user:any) : Observable<T> {
    return this.post<T>(this.apiUrl + '/is-email-exists', user);
  }

  protected changePasswordApi<T>(user:any) : Observable<T> {
    return this.post<T>(this.apiUrl + '/change-password', user);
  }
}