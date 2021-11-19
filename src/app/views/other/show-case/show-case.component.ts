import { Component, OnInit } from '@angular/core';

@Component({
    selector: 'show-case',
    templateUrl: './show-case.component.html',
    styleUrls: ['./show-case.component.scss']
})


export class ShowCaseComponent implements OnInit {

    ngOnInit() {}
    file: File;

    onFileAdd(file: File) {
    this.file = file;
    }
    
    onFileRemove() {
    this.file = null;
    }
    }