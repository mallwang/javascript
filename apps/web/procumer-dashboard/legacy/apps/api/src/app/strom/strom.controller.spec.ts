import {Test, TestingModule} from '@nestjs/testing';
import {StromController} from './strom.controller';

describe('StromController', () => {
  let controller: StromController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [StromController]
    }).compile();

    controller = module.get<StromController>(StromController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
