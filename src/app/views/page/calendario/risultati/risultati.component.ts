import { Component, Input, OnInit } from '@angular/core';
import { AlertService } from 'src/app/services/alert.service';
import { MatDialog } from '@angular/material/dialog';
import { MyModalMatch } from 'src/app/components/my-modal-match/my-modal-match.component';
import { PlayerService } from 'src/app/services/player.service';
import { GlobalComponent } from 'src/app/classes/utils/global-component';

@Component({
  selector: 'risultati',
  templateUrl: './risultati.component.html',
  styleUrls: ['./risultati.component.scss']
})
export class RisultatiComponent extends GlobalComponent implements OnInit {

  @Input() selected: any;

  constructor(
    private alert: AlertService,
    private playerService: PlayerService,
    public dialog: MatDialog,) {
    super();
  }

  ngOnInit() { }

  ngOnChanges() {console.log(this.selected)}


  viewMatch(partita: any) {

    this.playerService.viewMatch(partita)
      .subscribe({

        next: (result: any) => {
          this.dialog.open(MyModalMatch, { data: result, panelClass: 'dialog-match' });
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }



}
