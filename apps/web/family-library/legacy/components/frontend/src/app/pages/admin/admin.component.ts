import {Component, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {UserService} from '../../core/services/user.service';
import {User} from '../../interfaces/user';
import {ClrDatagridSortOrder} from '@clr/angular';
import {AuthService} from '../../core/services/auth.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';

type MODE = 'CREATE' | 'EDIT';

@Component({
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.scss']
})
export class AdminComponent implements OnInit {
  // Datagrid parameters
  public users: Array<User> = [];
  public descSort = ClrDatagridSortOrder.ASC;

  // Deletion confirmation modal
  public openDeletionConfirmation = false;
  public userToBeDeleted: User = null;
  public deleteUserAccountError = null;

  // Edit user modal and form
  public openUser = false;
  public userForm = new FormGroup({});
  public userError = null;
  public mode: 'CREATE' | 'EDIT' = 'CREATE';
  public roles = ['admin', 'user'];

  // Other
  public currentEmail;
  public loading = true;

  public constructor(
    private readonly authService: AuthService,
    private readonly titleService: Title,
    private readonly userService: UserService
  ) {
    this.titleService.setTitle('Administration');
  }

  public async ngOnInit(): Promise<any> {
    this.users = await this.userService.findAll();
    this.currentEmail = this.authService.email;
    this.loading = false;
  }

  /**
   * Create a new user form.
   */
  private createNewUserForm(): void {
    this.userForm = new FormGroup({
      firstName: new FormControl('', Validators.required),
      lastName: new FormControl('', Validators.required),
      email: new FormControl('', [Validators.email, Validators.required]),
      role: new FormControl('', Validators.required),
      password: new FormControl('', [Validators.minLength(8), Validators.required]),
      password2: new FormControl('', [Validators.minLength(8), Validators.required])
    });
  }

  /**
   * Create a edit user form.
   * @param {User} user: The user to be edited.
   */
  private createEditUserForm(user: User): void {
    this.userForm = new FormGroup({
      _id: new FormControl(user._id),
      firstName: new FormControl(user.firstName, Validators.required),
      lastName: new FormControl(user.lastName, Validators.required),
      email: new FormControl(user.email, [Validators.email, Validators.required]),
      role: new FormControl(user.role, Validators.required),
      password: new FormControl(user.password, [Validators.minLength(8), Validators.required]),
      password2: new FormControl(user.password, [Validators.minLength(8), Validators.required])
    });
  }

  /**
   * Open the user deletion confirmation modal.
   * @param {User} user: The user to be deleted.
   */
  public openDeletionConfirmationModal(user: User): void {
    this.userToBeDeleted = user;
    this.openDeletionConfirmation = true;
  }

  /**
   * Open the user creation modal.
   * @param {MODE} mode: The modal mode.
   * @param {User} user: The user to be edited.
   */
  public openUserModal(mode: MODE, user?: User): void {
    this.mode = mode;

    if (mode === 'CREATE') {
      this.createNewUserForm();
    } else if (mode === 'EDIT') {
      this.createEditUserForm(user);
    }

    this.openUser = true;
  }

  /**
   * Save the user form.
   */
  public async saveUser(): Promise<any> {
    // Reset the alerts
    this.userError = null;

    try {
      // Save user form
      const newUser: User = this.userForm.getRawValue();
      newUser.email = newUser.email.toLowerCase();

      // Check whether the password fiels matches
      if (this.userForm.controls['password'].value !== this.userForm.controls['password2'].value) {
        this.userError = 'Beide Passwörter stimmen nicht überein.';
        return;
      }

      let savedUser;
      if (this.mode === 'CREATE') {
        savedUser = await this.authService.register(newUser);
      } else if (this.mode === 'EDIT') {
        await this.userService.update(newUser._id, newUser);
      }

      if (this.mode === 'CREATE') {
        // Add user to the list of users
        this.users.push(savedUser);
      } else if (this.mode === 'EDIT') {
        // Replace the existing user in the list of users
        const userIndex = this.users.findIndex((user: User) => {
          return user._id === newUser._id;
        });
        this.users[userIndex] = newUser;
      }

      this.openUser = false;

      this.userForm.reset();
    } catch (error) {
      console.error(error.error.error);
      this.userError = `Es ist ein Fehler aufgetreten: ${error.error.error}`;
    }
  }

  /**
   * Delete a user.
   */
  public async deleteUser(): Promise<any> {
    try {
      // Delete a user
      const deletedUser = await this.userService.delete(this.userToBeDeleted._id);

      // Remove the deleted user from the users list
      const userIndex = this.users.findIndex((user: User) => {
        return user._id === deletedUser._id;
      });
      this.users.splice(userIndex, 1);

      // Close the modal
      this.openDeletionConfirmation = false;
    } catch (error) {
      console.error(error.error.error);
      this.deleteUserAccountError = `Es ist ein Fehler aufgetreten: ${error.error.error}`;
    }
  }
}
