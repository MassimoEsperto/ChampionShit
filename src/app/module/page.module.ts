import { PagamentoComponent } from './../views/other/pagamento/pagamento.component';
import { GestioneNotificheComponent } from './../views/page/gestione-admin/gestione-notifiche/gestione-notifiche.component';
import { RisultatiComponent } from '../views/page/calendario/risultati/risultati.component';
import { CalendarioComponent } from '../views/page/calendario/calendario.component';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AuthGuard } from 'src/app/classes/guard/auth-guard';
import { DashboardComponent } from '../views/page/dashboard/dashboard.component';
import { SidebarComponent } from '../components/sidebar/sidebar.component';
import { PageRoutingModule } from './page-routing.module';
import { FooterComponent } from '../components/footer/footer.component';
import { AppCustomFrameworkModule } from './framework.module';
import { SharedModule } from './shared.module';
import { InfoUtenteComponent } from '../views/page/info-utente/info-utente.component';
import { RoseUtentiComponent } from '../views/page/rose-utenti/rose-utenti.component';
import { SchieramentoComponent } from '../views/page/schieramento/schieramento.component';
import { GestioneAdminComponent } from '../views/page/gestione-admin/gestione-admin.component';
import { GestioneRoseComponent } from '../views/page/gestione-admin/gestione-rose/gestione-rose.component';
import { CalcoloVotiComponent } from '../views/page/gestione-admin/calcolo-voti/calcolo-voti.component';
import { GestioneSquadreComponent } from '../views/page/gestione-admin/gestione-squadre/gestione-squadre.component';
import { GestioneUtentiComponent } from '../views/page/gestione-admin/gestione-utenti/gestione-utenti.component';
import { RecuperoPrecedentiComponent } from '../views/page/gestione-admin/recupero-precedenti/recupero-precedenti.component';
import { ComunicazioniComponent } from '../views/page/comunicazioni/comunicazioni.component';
import { SorteggiRandomComponent } from '../views/page/gestione-admin/accoppiamenti/sorteggi-random/sorteggi-random.component';
import { NavDesktopComponent } from '../components/navigator/nav-desktop/nav-desktop.component';
import { NavSmartphoneComponent } from '../components/navigator/nav-smartphone/nav-smartphone.component';
import { NavTabletComponent } from '../components/navigator/nav-tablet/nav-tablet.component';
import { VotiLiveComponent } from '../views/page/voti-live/voti-live.component';
import { ClassificheComponent } from '../views/page/classifiche/classifiche.component';
import { StatisticheComponent } from '../components/statistiche/statistiche.component';
import { DataPartiteComponent } from '../views/page/gestione-admin/accoppiamenti/data-partite/data-partite.component';
import { UpgradeTeamComponent } from '../components/upgrade-team/upgrade-team.component';
import { MyLogoComponent } from '../components/my-logo/my-logo.component';
import { AccoppiamentiComponent } from '../views/page/gestione-admin/accoppiamenti/accoppiamenti.component';
import { EliminatorieComponent } from '../views/page/gestione-admin/accoppiamenti/eliminatorie/eliminatorie.component';
import { RegistraSquadraComponent } from '../components/registra-squadra/registra-squadra.component';
import { IscrizioneComponent } from '../views/page/iscrizione/iscrizione.component';
import { AggiornaRosaComponent } from '../views/page/aggiorna-rosa/aggiorna-rosa.component';



@NgModule({
  declarations: [
    DashboardComponent,  
    SchieramentoComponent,
    SidebarComponent,
    FooterComponent,
    CalendarioComponent,
    InfoUtenteComponent,
    RisultatiComponent,
    RoseUtentiComponent,
    GestioneAdminComponent,
    CalcoloVotiComponent,
    GestioneRoseComponent,
    GestioneSquadreComponent,
    GestioneUtentiComponent,
    RecuperoPrecedentiComponent,
    ComunicazioniComponent,
    GestioneNotificheComponent,
    PagamentoComponent,
    SorteggiRandomComponent,
    NavDesktopComponent,
    NavSmartphoneComponent,
    NavTabletComponent,
    VotiLiveComponent,
    ClassificheComponent,
    StatisticheComponent,
    DataPartiteComponent,
    UpgradeTeamComponent,
    MyLogoComponent,
    AccoppiamentiComponent,
    EliminatorieComponent,
    RegistraSquadraComponent,
    IscrizioneComponent,
    AggiornaRosaComponent
  ],
  imports: [
    CommonModule,
    PageRoutingModule,
    FormsModule,                              
    ReactiveFormsModule,
    SharedModule,
    AppCustomFrameworkModule              
  
  ],
  providers: [AuthGuard]
})
export class PageModule { }
