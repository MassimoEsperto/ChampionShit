import { Component, OnInit, OnDestroy } from '@angular/core';
import { Subscription } from 'rxjs';
import { SpinnerService } from 'src/app/services/spinner.service';

@Component({
  selector: 'my-spinner',
  templateUrl: './my-spinner.component.html',
  styleUrls: ['./my-spinner.component.scss']
})
export class MySpinner implements OnInit, OnDestroy {
  private subscription: Subscription;
  loading: boolean;

  constructor(private load: SpinnerService) { }

  ngOnInit() {
      this.subscription = this.load.getSpinner()
          .subscribe(result => {
              this.loading = result;
          });
  }

  ngOnDestroy() {
      this.subscription.unsubscribe();
  }

  closeAlert() {
      this.load.clear()
  }
}
