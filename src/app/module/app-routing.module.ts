import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { RecuperaPasswordComponent } from '../views/login/recupera-password/recupera-password.component';
import { RegisterComponent } from '../views/login/register/register.component';
import { SignInComponent } from '../views/login/sign-in/sign-in.component';
import { PresentationComponent } from '../views/other/presentation/presentation.component';
import { ShowCaseComponent } from '../views/other/show-case/show-case.component';


const routes: Routes = [
    {
        path: '',
        redirectTo: '/login',
        pathMatch: 'full'
    },
    {
        path: 'login',
        component: PresentationComponent,
        data: { animation: 'HomePage' }
    },
    {
        path: 'sign-in',
        component: SignInComponent,
        data: { animation: 'AboutPage' }
    },
    {
        path: 'register',
        component: RegisterComponent,
        data: { animation: 'AboutPage' }
    },
    {
        path: 'recupera-password',
        component: RecuperaPasswordComponent,
        data: { animation: 'HomePage' }
    },
    {
        path: 'show-case',
        component: ShowCaseComponent,
        data: { animation: 'HomePage' }
    },
];

@NgModule({
    imports: [RouterModule.forRoot(routes,{useHash: true})],
    exports: [RouterModule]
})
export class AppRoutingModule { }
