import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, ParamMap } from '@angular/router';
import { finalize, timeout } from 'rxjs/operators';
import { Squadra } from 'src/app/classes/models/squadra';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { AlertService } from 'src/app/services/alert.service';
import { AuthService } from 'src/app/services/auth.service';
import { PlayerService } from 'src/app/services/player.service';
import { SpinnerService } from 'src/app/services/spinner.service';
import { Router } from '@angular/router';

@Component({
  selector: 'iscrizione',
  templateUrl: './iscrizione.component.html',
  styleUrls: ['./iscrizione.component.scss']
})
export class IscrizioneComponent extends GlobalComponent implements OnInit {

  info: any
  registra: boolean = false;

  constructor(
    private router: Router,
    private authService: AuthService,
    private playerService: PlayerService,
    private spinner: SpinnerService,
    public dialog: MatDialog,
    private route: ActivatedRoute,
    private alert: AlertService) {
    super();
  }

  ngOnInit() {
    this.getInfoUtente();
  }

  goBack() {
    this.router.navigate(['login']);
  }

  onDelete(id: any) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.delSquadra(id);
    });
  }

  getInfoUtente() {

    this.startPage(this.spinner);

    this.playerService.getInfoUtente()
      .pipe(finalize(() => {
        this.loadPage(this.spinner);
      }))
      .subscribe({
        next: (result: any) => {
          this.info = result;
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }


  delSquadra(id: any) {

    this.loading_btn = true;

    let payload = { id: id }

    this.playerService.delSquadra(payload)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {

          this.alert.success(this.language.alert.success);
          this.getInfoUtente();

        },
        error: (error: any) => {
          this.alert.error(error);
        },

      })
  }

}
