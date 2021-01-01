import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Routes, RouterModule } from '@angular/router';

import { CategoryComponent } from './category.component';
import { CategoryListComponent } from './category-list/category-list.component';
import { AddOrEditCategoryComponent } from './add-or-edit-category/add-or-edit-category.component';

const routes: Routes = [
  {
    path: '',
    component: CategoryComponent,
    children: [
      {
        path: 'category-list',
        component: CategoryListComponent
      },
      {
        path: 'add-or-edit-category',
        component: AddOrEditCategoryComponent
      },
      {
        path: '',
        redirectTo: 'category-list',
        pathMatch: 'full'
      }
    ]
  }
];

@NgModule({
  declarations: [
    CategoryComponent, 
    CategoryListComponent, 
    AddOrEditCategoryComponent
  ],
  imports: [
    CommonModule,
    RouterModule.forChild(routes)
  ]
})
export class CategoryModule { }
