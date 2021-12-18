import { CalendarioComponent } from '../views/page/calendario/calendario.component';

import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AuthGuard } from 'src/app/classes/guard/auth-guard';
import { SidebarComponent } from '../components/sidebar/sidebar.component';
import { DashboardComponent } from '../views/page/dashboard/dashboard.component';
import { InfoUtenteComponent } from '../views/page/info-utente/info-utente.component';
import { RoseUtentiComponent } from '../views/page/rose-utenti/rose-utenti.component';
import { SchieramentoComponent } from '../views/page/schieramento/schieramento.component';
import { GestioneAdminComponent } from '../views/page/gestione-admin/gestione-admin.component';
import { ComunicazioniComponent } from '../views/page/comunicazioni/comunicazioni.component';
import { VotiLiveComponent } from '../views/page/voti-live/voti-live.component';
import { ClassificheComponent } from '../views/page/classifiche/classifiche.component';



const routes: Routes = [
    {
        path: 'home',
        component: SidebarComponent,
        canActivate: [AuthGuard],
        children: [
            {
                path: 'gestione-admin',
                component: GestioneAdminComponent
            },
            {
                path: 'dashboard',
                component: DashboardComponent
            },
            {
                path: 'schieramento',
                component: SchieramentoComponent
            },
            {
                path: 'voti-live',
                component: VotiLiveComponent
            },
            {
                path: 'calendario',
                component: CalendarioComponent
            },
            {
                path: 'info-utente',
                component: InfoUtenteComponent
            },
            {
                path: 'rose-utenti',
                component: RoseUtentiComponent
            }
            ,
            {
                path: 'comunicazioni',
                component: ComunicazioniComponent
            }
            ,
            {
                path: 'classifiche',
                component: ClassificheComponent
            }
        ]
    }
];


@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PageRoutingModule { }
