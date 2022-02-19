import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Utente } from 'src/app/classes/models/utente';
import { PlayerService } from 'src/app/services/player.service';

@Component({
  selector: 'my-logo',
  templateUrl: './my-logo.component.html',
  styleUrls: ['./my-logo.component.scss']
})
export class MyLogoComponent implements OnInit {

  @Input() is_upgrade: any;
  
  constructor(
    private playerService: PlayerService,
    private router: Router
  ) { }

  loggato: Utente;

  ngOnInit() { this.loggato = this.playerService.getLoggato(); }

  visualizzaProfilo() {
    this.router.navigate(['/home/info-utente']);
  }

  upgradeTeam() {
    this.router.navigate(['/home/info-utente'], { queryParams: { upgrade: this.is_upgrade } });
  }

}
