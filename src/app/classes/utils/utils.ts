import { Ruolo, ComboRuolo } from './enums';
import { ElementRef } from '@angular/core';

export class Utils{

    public static backgroundBlue(elementRef: ElementRef){
        elementRef.nativeElement.ownerDocument.body.style.backgroundColor = '#1B98CE';
    }

    public static backgroundRed(elementRef: ElementRef){
        elementRef.nativeElement.ownerDocument.body.style.backgroundColor = '#FF0000';
    }

    public static backgroundWhite(elementRef: ElementRef){
        elementRef.nativeElement.ownerDocument.body.style.backgroundColor = 'white';
    }

    public static backgroundGreen(elementRef: ElementRef){
        elementRef.nativeElement.ownerDocument.body.style.backgroundColor = '#50c878';
    }

    public static backgroundCelestino(elementRef: ElementRef){
        elementRef.nativeElement.ownerDocument.body.style.backgroundColor = 'rgb(250, 246, 246, 0.986)';
    }

    public static backgroundAzzurro(elementRef: ElementRef){
        elementRef.nativeElement.ownerDocument.body.style.backgroundImage = 'radial-gradient(#abbcff, #912dd2)';
    }
}

