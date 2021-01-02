import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Observable } from 'rxjs';

export class BaseApiService {

  protected baseApiUrl: string;
  constructor(private _http : HttpClient) {
    this.baseApiUrl = environment.apiURL;
  }

  protected get<T>(url:string) : Observable<T>{
    return this._http.get<T>(this.baseApiUrl + url);
  }

  post<T>(url:string, body: any) : Observable<T>{
    return this._http.post<T>(this.baseApiUrl + url, body);
  }

  protected put<T>(url:string, body: any) : Observable<T>{
    return this._http.put<T>(this.baseApiUrl + url, body);
  }

  protected delete<T>(url:string) : Observable<T>{
    return this._http.delete<T>(this.baseApiUrl + url);
  }
}