
export class Formatting {

  

  schieramenti(input: any) {

    let result = []
    try {
      let squadra: string = input[0].id_utente;
      let schieramenti = [];

      for (let singolo of input) {
        if (singolo.id_utente == squadra) {
          schieramenti.push(singolo)
        }
        else {
          if (schieramenti.length > 1) {
            for (let i = 1; i < schieramenti.length; i++)
              result.push(schieramenti[i])
          } else {
            let item = schieramenti[0];
            for (let j = 1; j < 6; j++) {
              let record = {
                "id_partita": item.id_partita,
                "girone": item.girone,
                "squadra": item.squadra,
                "id_utente": item.id_utente,
                "schieramento": j,
                "id_calciatore": "0",
                "calciatore": "NULLO",
                "ruolo": "N",
                "voto": null
              }
              result.push(record)
            }


          }
          schieramenti = []
          schieramenti.push(singolo)
          squadra = singolo.id_utente;

        }
      }
      if (schieramenti.length > 1) {
        for (let i = 1; i < schieramenti.length; i++)
          result.push(schieramenti[i])
      } else {
        let item = schieramenti[0];
        for (let j = 1; j < 6; j++) {
          let record = {
            "id_partita": item.id_partita,
            "girone": item.girone,
            "squadra": item.squadra,
            "id_utente": item.id_utente,
            "schieramento": j,
            "id_calciatore": "0",
            "calciatore": "NULLO",
            "ruolo": "N",
            "voto": null
          }
          result.push(record)
        }
      }
    } catch (error) {
      return result;
    }

    return result;

  }

  formazioniPerGara(input: any) {

    let result = [];

    try {
      let partite = [];

      for (let ele of input) {

        let esiste = partite.some(x => x == ele.id_partita)
        if (!esiste) {
          partite.push(ele.id_partita)

          let casa = []
          let trasferta = []
          let match = input.filter(x => x.id_partita == ele.id_partita);

          for (let i = 0; i < 5; i++) {
            casa.push(match[i])
          }
          for (let i = 5; i < 10; i++) {
            trasferta.push(match[i])
          }

          let singola = {
            id_partita: ele.id_partita,
            casa: {
              squadra: casa[0].squadra,
              id_utente: casa[0].id_utente,
              formazione: casa
            },
            trasferta: {
              squadra: trasferta[0].squadra,
              id_utente: trasferta[0].id_utente,
              formazione: trasferta
            }
          }
          result.push(singola)
        }
      }
    } catch (error) {
      return result;
    }

    return result;
  }

  palinsesto(input: any, filelist: any) {

    let result = [];
    try {
      let partite = [];

      for (let ele of input) {
        let tmp = filelist.find(x => x['nome'] == ele['calciatore']);

        ele.voto = tmp ? Number(tmp.voto) : 4;
        let esiste = partite.some(x => x == ele.id_partita)
        if (!esiste) {
          partite.push(ele.id_partita)

          let casa = []
          let trasferta = []
          let match = input.filter(x => x.id_partita == ele.id_partita);

          for (let i = 0; i < 5; i++) {
            casa.push(match[i])
          }
          for (let i = 5; i < 10; i++) {
            trasferta.push(match[i])
          }

          let singola = {
            id_partita: ele.id_partita,
            casa: {
              squadra: casa[0].squadra,
              id_utente: casa[0].id_utente,
              totale: 0,
              gol: 0,
              formazione: casa
            },
            trasferta: {
              squadra: trasferta[0].squadra,
              id_utente: trasferta[0].id_utente,
              totale: 0,
              gol: 0,
              formazione: trasferta
            }
          }
          result.push(singola)
        }
      }
    } catch (error) {
      return result;
    }

    return result;
  }


  payloadCalcolo(risultati: any) {

    let payload_voti = [];
    let payload_ris = [];
    for (let ris of risultati) {
      let voti_casa = {
        id_partita: ris.id_partita,
        id_utente: ris.casa.id_utente,
        lista: ris.casa.formazione,
      }
      let voti_trasf = {
        id_partita: ris.id_partita,
        id_utente: ris.trasferta.id_utente,
        lista: ris.trasferta.formazione,
      }

      let ris_match = {
        id_partita: ris.id_partita,
        gol_casa: ris.casa.gol,
        gol_trasferta: ris.trasferta.gol,
        pt_casa: ris.casa.gol > ris.trasferta.gol ? 3 : ris.casa.gol == ris.trasferta.gol ? 1 : 0,
        pt_trasferta: ris.trasferta.gol > ris.casa.gol ? 3 : ris.casa.gol == ris.trasferta.gol ? 1 : 0
      }

      payload_voti.push(voti_casa);
      payload_voti.push(voti_trasf);
      payload_ris.push(ris_match)
    }

    return { voti: payload_voti, ris: payload_ris }
  }


}