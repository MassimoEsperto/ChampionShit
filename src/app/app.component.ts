import { Component, AfterViewInit, ElementRef } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { slideInAnimation } from './module/animations';


@Component({
    selector: 'app-root',
    animations: [
        slideInAnimation
        // animation triggers go here
    ],
    template: ` <div [@routeAnimations]="prepareRoute(outlet)" >
              <router-outlet #outlet="outlet"></router-outlet></div>
              <my-alert></my-alert>`

})
export class AppComponent implements AfterViewInit {

    constructor(private elementRef: ElementRef) { }

    ngOnInit() {}

    ngAfterViewInit() {
       //this.elementRef.nativeElement.ownerDocument.body.style.backgroundColor = '#00b050';
    }

    prepareRoute(outlet: RouterOutlet) {
        return outlet && outlet.activatedRouteData && outlet.activatedRouteData['animation'];
    }

   
}