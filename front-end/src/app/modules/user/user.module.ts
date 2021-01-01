import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Routes, RouterModule } from '@angular/router';

import { UserProfileComponent } from './user-profile/user-profile.component';
import { UserComponent } from './user.component';

const routes: Routes = [
  {
    path: '',
    component: UserComponent,
    children: [
      {
        path: 'user-profile',
        component: UserProfileComponent
      }, 
      {
        path: '',
        redirectTo: 'user-profile',
        pathMatch: 'full'
      }
    ]
  }
];

@NgModule({
  declarations: [
    UserProfileComponent, 
    UserComponent
  ],
  imports: [
    CommonModule,
    RouterModule.forChild(routes)
  ]
})
export class UserModule { }
