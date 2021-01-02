import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { AuthenticationService } from '../../services/authentication.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {

  registerForm: FormGroup;
  isFormSubmitted: boolean;

  constructor(private _authenticationService:AuthenticationService) {
    this.registerForm = new FormGroup({
      email: new FormControl('', [Validators.required, Validators.email]),
      gender: new FormControl('', [Validators.required]),
      birthDate: new FormControl('', [Validators.required]),
      password: new FormControl('', [Validators.required]),
      confirmPassword: new FormControl('', [Validators.required])  
    });

    this.isFormSubmitted = false;
  }

  ngOnInit(): void {
  }

  onSubmitForm():void {
    this.isFormSubmitted = true;

    if(this.registerForm.invalid){
      return;
    }

    this._authenticationService.register(this.registerForm.value)
    .subscribe(response => {

    });
  }
}
