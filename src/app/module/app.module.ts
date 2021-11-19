import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from '../app.component';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { ShowCaseComponent } from '../views/other/show-case/show-case.component';
import { ErrorPageComponent } from '../views/other/error-page/error-page.component';
import { PresentationComponent } from '../views/other/presentation/presentation.component';
import { SignInComponent } from '../views/login/sign-in/sign-in.component';
import { RegisterComponent } from '../views/login/register/register.component';
import { RecuperaPasswordComponent } from '../views/login/recupera-password/recupera-password.component';
import { PageModule } from './page.module';
import { AppCustomFrameworkModule } from './framework.module';
import { CommonModule } from '@angular/common';
import { SharedModule } from './shared.module';


@NgModule({
  declarations: [
    AppComponent,
    ShowCaseComponent,
    ErrorPageComponent,
    PresentationComponent,
    SignInComponent,
    RegisterComponent,
    RecuperaPasswordComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    CommonModule,
    HttpClientModule,
    BrowserAnimationsModule,
    PageModule,
    AppCustomFrameworkModule,
    SharedModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
