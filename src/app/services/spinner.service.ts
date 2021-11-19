import { Injectable } from '@angular/core';
import { Router, NavigationStart } from '@angular/router';
import { Observable, Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SpinnerService {
  private subject = new Subject<any>();
  private keepAfterRouteChange = false;

  constructor(private router: Router) {
      // clear on route change unless 'keepAfterRouteChange' flag is true
      this.router.events.subscribe(event => {
          if (event instanceof NavigationStart) {
              if (this.keepAfterRouteChange) {
                  // only keep for a single route change
                  this.keepAfterRouteChange = false;
              } else {
                  // clear spinner
                  this.clear();
              }
          }
      });
  }

  getSpinner(): Observable<any> {
      return this.subject.asObservable();
  }

  view(keepAfterRouteChange = false) {
      this.keepAfterRouteChange = keepAfterRouteChange;
      this.subject.next({ boolean: true });
  }

  clear() {
      // clear by calling subject.next() without parameters
      this.subject.next();
  }

  
}