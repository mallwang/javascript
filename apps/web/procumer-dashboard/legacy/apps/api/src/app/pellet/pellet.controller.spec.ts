import {Test, TestingModule} from '@nestjs/testing';
import {PelletController} from './pellet.controller';

describe('PelletController', () => {
  let controller: PelletController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [PelletController]
    }).compile();

    controller = module.get<PelletController>(PelletController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
