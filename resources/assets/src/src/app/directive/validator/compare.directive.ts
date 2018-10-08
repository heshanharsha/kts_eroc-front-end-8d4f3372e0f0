import { Validators, AbstractControl, ValidationErrors, NG_VALIDATORS, ValidatorFn } from '@angular/forms';
import { Directive, Input } from '@angular/core';
import { Subscription } from 'rxjs';

export function compareValidator(ControlName: string): ValidatorFn {
  return (c: AbstractControl): ValidationErrors | null => {
    if (c.value === null || c.value.length === 0) {
      return null;
    }
    const controlToCompare = c.root.get(ControlName);
    if (controlToCompare) {
      const subscription: Subscription = controlToCompare.valueChanges.subscribe(() => {
        c.updateValueAndValidity();
        subscription.unsubscribe();
      });
    }
    return controlToCompare && controlToCompare.value !== c.value ? { 'compare': true } : null;
  };
}

export function NicValidator(ControlName: string): ValidatorFn {
  return (c: AbstractControl): ValidationErrors | null => {
    if (c.value === null || c.value.length === 0) {
      return null;
    }
    const controlToCompare = c.root.get(ControlName);
    if (controlToCompare) {
      const subscription: Subscription = controlToCompare.valueChanges.subscribe(() => {
        c.updateValueAndValidity();
        subscription.unsubscribe();
      });
    }
    return controlToCompare && controlToCompare.value !== c.value ? { 'compare': true } : null;
  };
}

export function NameValidator(name: string): ValidatorFn {
  return (c: AbstractControl): ValidationErrors | null => {
    if (c.value === null || c.value.length === 0) {
      return null;
    }

    const transformedInput = name.replace(/[^A-Za-z ]/g, '');
    const controlToCompare = c.root.get(name);

    return controlToCompare.value < 1 && transformedInput !== c.value ? { 'specialChar': true } : null;
  };

}

@Directive({
  // tslint:disable-next-line:directive-selector
  selector: '[Compare]',
  providers: [{ provide: NG_VALIDATORS, useExisting: CompareDirective, multi: true }]
})

export class CompareDirective implements Validators {

  constructor() { }

}
