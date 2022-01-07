
export class Formatting {


  payloadCalcolo(risultati: any) {

    let payload_voti = [];
    let payload_ris = [];
    for (let ris of risultati) {
     
      let voti_casa = {
        id_partita: ris.id_partita,
        id_utente: ris.match[0].id_utente,
        lista: ris.match[0].schieramento,
      }
      let voti_trasf = {
        id_partita: ris.id_partita,
        id_utente: ris.match[1].id_utente,
        lista: ris.match[1].schieramento,
      }

      let ris_match = {
        id_partita: ris.id_partita,
        gol_casa: ris.match[0].gol,
        gol_trasferta: ris.match[1].gol,
        pt_casa: ris.match[0].gol > ris.match[1].gol ? 3 : ris.match[0].gol == ris.match[1].gol ? 1 : 0,
        pt_trasferta: ris.match[1].gol > ris.match[0].gol ? 3 : ris.match[0].gol == ris.match[1].gol ? 1 : 0
      }

      payload_voti.push(voti_casa);
      payload_voti.push(voti_trasf);
      payload_ris.push(ris_match)
    }

    return { voti: payload_voti, ris: payload_ris }
  }

  palinsesto(filelist: any, formazioni_inserite: any) {

    try {

      for (let record of formazioni_inserite) {

        for (let match of record['match']) {
          let punti = 0;

          for (let singolo of match['schieramento']) {
            let tmp = filelist.find(x => x['nome'] == singolo['calciatore']);
            singolo.voto = tmp ? Number(tmp.voto) : 4;
            punti = punti + singolo.voto;
          }
          match.punti = punti;
          match.gol = this.gol(punti);
        }
      }

    } catch (error) {
      return formazioni_inserite;
    }
   
    return formazioni_inserite;
  }

  gol(punti: number) {

    if (punti < 30) {
      return 0
    } else {
      let tmp: any = (punti - 27) / 3;
      return parseInt(tmp).toFixed(0);
    }
  }

}