import { BaseApiService } from '../../shared/services/base-api.service';
import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';

export class AuthenticationApiService extends BaseApiService{

  private apiUrl : string;

  constructor( _http : HttpClient) {
    super(_http);
    this.apiUrl = '/auth';
  }

  protected register<T>(user:any) : Observable<T> {
    return this.post<T>(this.apiUrl + '/register', user);
  }

  protected login<T>(user:any) : Observable<T> {
    return this.post<T>(this.apiUrl + '/login', user);
  }

  protected isEmailExists<T>(user:any) : Observable<T> {
    return this.post<T>(this.apiUrl + '/is-email-exists', user);
  }

  protected changePassword<T>(user:any) : Observable<T> {
    return this.post<T>(this.apiUrl + '/change-password', user);
  }

  protected loginWithProviderApi<T>(user:any) : Observable<T> {
    return this.post<T>(this.apiUrl + '/signin-with-provider', user);
  }
}