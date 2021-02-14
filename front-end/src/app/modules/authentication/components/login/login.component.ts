import { Component, OnInit } from '@angular/core';

import { ProviderUserDetailsModel } from './../../models'
import { AuthenticationService } from '../../services/authentication.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  googleAuth : any;

  constructor(private authService : AuthenticationService) { 

  }

  ngOnInit(): void {
    const gapi = ( window as any).gapi;

    gapi.load('client:auth2', () => {
      gapi.client.init({
          clientId: '990476272840-3dul0qa9vjjsqsmrbm7ugiljj47d90pt.apps.googleusercontent.com',
          scope: 'email'
      }).then(() => {
          this.googleAuth  = gapi.auth2.getAuthInstance();

          if(this.googleAuth.isSignedIn.get()){
            this.googleAuth.signOut();
          }

          this.googleAuth.isSignedIn.listen(( isSignedIn : boolean) => {
            if(!isSignedIn){
              return;
            }

            const userDetails = new ProviderUserDetailsModel(
              this.googleAuth.currentUser.get().getBasicProfile().getId(),
              this.googleAuth.currentUser.get().getBasicProfile().getEmail(),
              this.googleAuth.currentUser.get().getBasicProfile().getGivenName(),
              this.googleAuth.currentUser.get().getBasicProfile().getFamilyName(),
              'Google' 
            );
              
            this.onLoginWithProviders(userDetails);
          });
      });
    });
  }

  onClickLoginWithGoogle(){
    this.googleAuth.signIn();
  }

  onLoginWithProviders(userDetails : ProviderUserDetailsModel){
    this.authService.loginWithProvider(userDetails)
    .subscribe(response => {
      
    })
  }
}
